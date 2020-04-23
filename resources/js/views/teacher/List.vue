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
          <span>{{ scope.row.position }}</span>
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
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.teacher_id, scope.row.name);">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <el-dialog v-model="isEdit" title="增加/编辑教师" :visible.sync="dialogFormVisible">
      <div v-loading="teacherCreating" class="form-container">
        <el-form ref="userForm" :rules="rules" :model="newTeacher" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="名称" prop="name">
            <el-input v-model="newTeacher.name" />
          </el-form-item>
          <el-form-item label="职位" prop="position">
            <el-input v-model="newTeacher.position" />
          </el-form-item>
          <el-form-item label="简介" prop="introduction">
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

const userResource = new UserResource();
const permissionResource = new Resource('permissions');

export default {
  name: 'TeacherList',
  components: { Pagination },
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
        introduction: [{ required: true, message: '教师简介', trigger: 'blur' }],
      },
      isEdit: false,
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
      this.$confirm('确定删除分类名为 ' + name + '吗?', 'Warning', {
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
            updateTeacher(this.newTeacher)
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
        position: '',
        introduction: '',
      };
    },
    formatJson(filterVal, jsonData) {
      return jsonData.map(v => filterVal.map(j => v[j]));
    },
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
</style>
