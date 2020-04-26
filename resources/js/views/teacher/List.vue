<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="query.keyword" placeholder="请输入关键词" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.teacher_id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="教师名称">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="教师职位">
        <template slot-scope="scope">
          <span>{{ scope.row.position.join(',') }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="教师图片">
        <template slot-scope="scope">
          <el-image
            style="width: 80px; height: 80px"
            :src="scope.row.images[0].url"
            :preview-src-list="scope.row.images.forEach(item=>{return item.url})"
          ></el-image>
        </template>
      </el-table-column>
      <el-table-column align="center" label="教师简介">
        <template slot-scope="scope">
          <span>{{ scope.row.introduction }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="创建时间">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="更新时间">
        <template slot-scope="scope">
          <span>{{ scope.row.updated_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="操作" width="350">
        <template slot-scope="scope">
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEdit(scope.row)">
            编辑
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.teacher_id, scope.row.name)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <el-dialog v-model="isEdit" title="增加/编辑教师" :visible.sync="dialogFormVisible">
      <div v-loading="teacherCreating" class="form-container">
        <el-form ref="userForm" :rules="rules" :model="newTeacher" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="教师名称:" prop="name">
            <el-input v-model="newTeacher.name" />
          </el-form-item>
          <el-form-item label="职位:" prop="position">
            <el-tag
              :key="tag"
              v-for="tag in newTeacher.position"
              closable
              :disable-transitions="false"
              @close="handleTagClose(tag)">
              {{tag}}
            </el-tag>
            <el-input
              class="input-new-tag"
              v-if="inputVisible"
              v-model="inputValue"
              ref="saveTagInput"
              size="small"
              @keyup.enter.native="handleInputConfirm"
              @blur="handleInputConfirm"
            >
            </el-input>
            <el-button v-else class="button-new-tag" size="small" @click="showInput">添加</el-button>
            <span>填写后按回车</span>
          </el-form-item>
          <el-form-item label="教师图片:" prop="images">

           <multiple-image :file-list="newTeacher.images"></multiple-image>
          </el-form-item>
          <el-form-item label="简介:" prop="introduction">
            <el-input type="textarea" v-model="newTeacher.introduction"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false;isEdit=false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="createTeacher()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import UserResource from '@/api/user';
import Resource from '@/api/resource';
import waves from '@/directive/waves'; // Waves directive
import { fetchList, updateTeacher, createTeacher, deleteTeacher } from '@/api/teacher';
import {getToken} from "@/utils/auth";
import MultipleImage from "@/components/Upload/MultipleImage";

const userResource = new UserResource();
const permissionResource = new Resource('permissions');

export default {
  name: 'TeacherList',
  components: {MultipleImage, Pagination },
  directives: { waves },
  data() {
    return {
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      teacherCreating: false,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
      },
      newTeacher: {},
      dialogFormVisible: false,
      rules: {
        name: [{ required: true, message: '教师名称必须', trigger: 'blur' }],
        position: [{ required: true, message: '职位必填', trigger: 'blur' }],
          images: [{ required: true, message: '图片', trigger: 'blur' }],
        introduction: [{ required: true, message: '教师简介', trigger: 'blur' }],
      },
      isEdit: false,
      inputVisible: false,
      inputValue: ''
    };
  },
  computed: {
  },
  created() {
    this.resetNewTeacher();
    this.getList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data } = await fetchList(this.query);
      this.list = data.list;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = data.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetNewTeacher();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['userForm'].clearValidate();
      });
    },
    handleEdit(data){
      this.newTeacher = data;
      this.isEdit = true;
      this.dialogFormVisible = true;
    },
    handleDelete(id, name) {
      this.$confirm('确定删除名为 ' + name + '吗?', 'Warning', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        deleteTeacher(id).then(response => {
          this.$message({
            type: 'success',
            message: '已删除',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      }).catch(() => {
      });
    },
    createTeacher() {
      this.$refs['userForm'].validate((valid) => {
        if (valid) {
          this.teacherCreating = true;
          if (this.isEdit){
            let  id = this.newTeacher.teacher_id;
            updateTeacher(id,this.newTeacher)
              .then(response => {
                this.$message({
                  message: '成功',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetNewTeacher();
                this.dialogFormVisible = false;
                this.handleFilter();
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                this.teacherCreating = false;
              });
          } else {
            createTeacher(this.newTeacher)
              .then(response => {
                this.$message({
                  message: '成功',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetNewTeacher();
                this.dialogFormVisible = false;
                this.handleFilter();
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                this.teacherCreating = false;
              });
          }
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetNewTeacher() {
      this.newTeacher = {
        name: '',
        position: [],
        images:[],
        introduction: '',
      };
    },
    handleSuccess(response, file, fileList){
      this.newTeacher.image_id = response.file_id;
      this.newTeacher.image_url = response.url;
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
      handleTagClose(tag) {
          this.newTeacher.position.splice(this.newTeacher.position.indexOf(tag), 1);
          return false;
      },

      showInput() {
          this.inputVisible = true;
          this.$nextTick(() => {
              this.$refs.saveTagInput.$refs.input.focus();
          });
      },

      handleInputConfirm() {
          let inputValue = this.inputValue;
          if (inputValue) {
              this.newTeacher.position.push(inputValue);
          }
          this.inputVisible = false;
          this.inputValue = '';
      }
  },
};
</script>

<style lang="scss" scoped>
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
  .dialog-footer {
    text-align: left;
    padding-top: 0;
    margin-left: 150px;
  }
  .app-container {
    flex: 1;
    justify-content: space-between;
    font-size: 14px;
    padding-right: 8px;
    .block {
      float: left;
      min-width: 250px;
    }
    .clear-left {
      clear: left;
    }
  }
  /*上传图片样式*/
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
  /*标签样式*/
  .el-tag + .el-tag {
    margin-left: 10px;
  }
  .button-new-tag {
    margin-left: 10px;
    height: 32px;
    line-height: 30px;
    padding-top: 0;
    padding-bottom: 0;
  }
  .input-new-tag {
    width: 90px;
    margin-left: 10px;
    vertical-align: bottom;
  }
</style>
