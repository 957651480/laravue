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

    <el-table v-loading="tableLoading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.banner_id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="标题">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="图片">
        <template slot-scope="scope">
          <img :src="scope.row.image_url" width="40" height="40" />
        </template>
      </el-table-column>
      <el-table-column align="center" label="状态">
        <template slot-scope="scope">
          <span>{{ scope.row.show===10?'显示':'隐藏' }}</span>
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
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.banner_id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <el-dialog v-model="isEdit" title="增加/编辑轮播图" :visible.sync="dialogFormVisible">
      <div v-loading="BannerCreating" class="form-container">
        <el-form ref="userForm" :rules="rules" :model="newBanner" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="名称:" prop="title">
            <el-input v-model="newBanner.title" />
          </el-form-item>
          <el-form-item label="图片:" prop="image_id">
            <el-upload
              class="avatar-uploader"
              :show-file-list="false"
              action="api/file/upload"
              :on-success="handleSuccess"
              :headers="myHeaders"
            >
              <img v-if="newBanner.image_url" :src="newBanner.image_url" class="avatar">
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
          </el-form-item>
          <el-form-item label="状态:" prop="show">
            <el-radio v-model="newBanner.show" :label="10">显示</el-radio>
            <el-radio v-model="newBanner.show" :label="20">隐藏</el-radio>
          </el-form-item>
          <el-form-item label="排序:" prop="sort">
            <el-input-number v-model="newBanner.sort"></el-input-number>
            <span>排序越大越靠前</span>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false;isEdit=false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="createBanner()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import waves from '@/directive/waves'; // Waves directive
import { fetchList, updateBanner, createBanner, deleteBanner } from '@/api/banner';
import {getToken} from "@/utils/auth";

export default {
  name: 'BannerList',
  components: { Pagination },
  directives: { waves },
  data() {
    return {
      list: null,
      total: 0,
      tableLoading: true,
      BannerCreating: false,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
      },
      newBanner: {},
      dialogFormVisible: false,
      rules: {
        title: [{ required: true, message: '辩题必须', trigger: 'blur' }],
        image_id: [{ required: true, message: '图片必填', trigger: 'blur' }],
      },
      isEdit: false,
      myHeaders: { Authorization: 'Bearer ' + getToken() },
    };
  },
  computed: {
  },
  created() {
    this.resetNewBanner();
    this.getList();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.tableLoading = true;
      const { data } = await fetchList(this.query);
      this.list = data.list;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = data.total;
      this.tableLoading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetNewBanner();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['userForm'].clearValidate();
      });
    },
    handleEdit(data){
      this.newBanner = data;
      this.isEdit = true;
      this.dialogFormVisible = true;
    },
    handleDelete(id) {
      this.$confirm('确定删除吗?', 'Warning', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        deleteBanner(id).then(response => {
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
    createBanner() {
      this.$refs['userForm'].validate((valid) => {
        if (valid) {
          this.BannerCreating = true;
          if (this.isEdit){
            let  id = this.newBanner.banner_id;
            updateBanner(id,this.newBanner)
              .then(response => {
                this.$message({
                  message: '成功',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetNewBanner();
                this.dialogFormVisible = false;
                this.handleFilter();
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                this.BannerCreating = false;
              });
          } else {
            createBanner(this.newBanner)
              .then(response => {
                this.$message({
                  message: '成功',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetNewBanner();
                this.dialogFormVisible = false;
                this.handleFilter();
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                this.BannerCreating = false;
              });
          }
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetNewBanner() {
      this.newBanner = {
        title: '',
        image_id:null,
        image_url:'',
        show: 10,
        sort: 100,
      };
    },
    handleSuccess(response, file, fileList){
      this.newBanner.image_id = response.file_id;
      this.newBanner.image_url = response.url;
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
</style>
