<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="query.keyword" placeholder="请输入关键词" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">添加省</el-button>
    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.region_id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="名称">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="简称">
        <template slot-scope="scope">
          <span>{{ scope.row.short_name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="合并名称">
        <template slot-scope="scope">
          <span>{{ scope.row.parent_id }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="层级">
        <template slot-scope="scope">
          <span>{{ scope.row.level }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="拼音码">
        <template slot-scope="scope">
          <span>{{ scope.row.pinyin }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="长途区号">
        <template slot-scope="scope">
          <span>{{ scope.row.code }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="邮编">
        <template slot-scope="scope">
          <span>{{ scope.row.zip_code }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="首字母">
        <template slot-scope="scope">
          <span>{{ scope.row.first }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="350">
        <template slot-scope="scope">
          <el-button v-show="showChildAdd(scope.row)" type="primary" size="small" icon="el-icon-plus" @click="handleChildAdd(scope.row)">
            添加子地区
          </el-button>
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEdit(scope.row)">
            编辑
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.region_id, scope.row.name)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <el-dialog v-model="isEdit" title="增加/编辑地区" :visible.sync="dialogFormVisible">
      <div v-loading="regionCreating" class="form-container">
        <el-form ref="regionForm" :rules="rules" :model="newRegion" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="名称:" prop="name">
            <el-input v-model="newRegion.name" />
          </el-form-item>
          <el-form-item label="拼音码:" prop="pinyin">
            <el-input v-model="newRegion.pinyin" />
          </el-form-item>
          <el-form-item label="状态:" prop="show">
            <el-radio v-model="newRegion.show" :label="10">显示</el-radio>
            <el-radio v-model="newRegion.show" :label="20">隐藏</el-radio>
          </el-form-item>
          <el-form-item label="排序:" prop="sort">
            <el-input-number v-model="newRegion.sort"></el-input-number>
            <span>排序越大越靠前</span>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false;isEdit=false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="createRegion()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Resource from '@/api/resource';
import waves from '@/directive/waves'; // Waves directive
import { fetchList, updateRegion, createRegion, deleteRegion } from '@/api/region';
const permissionResource = new Resource('permissions');

export default {
  name: 'RegionList',
  components: { Pagination },
  directives: { waves },
  data() {
    return {
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      regionCreating: false,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
      },
      newRegion: {},
      dialogFormVisible: false,
      rules: {
        name: [{ required: true, message: '名称必须', trigger: 'blur' }],
      },
      isEdit: false,
    };
  },
  computed: {
  },
  created() {
    this.resetNewRegion();
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
    showChildAdd(data){
      if(data.level>=3){
        return false
      }
      return true;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleCreate() {
      this.resetNewRegion();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['regionForm'].clearValidate();
      });
    },
    handleChildAdd(data){
      debugger
      this.newRegion.parent_id=data.region_id;
      this.newRegion.level=data.level+1;
      this.dialogFormVisible = true;
    },
    handleEdit(data){
      this.newRegion = data;
      this.isEdit = true;
      this.dialogFormVisible = true;
    },
    handleDelete(id, name) {
      this.$confirm('确定删除名为 ' + name + '吗?', '警告').then(() => {
        deleteRegion(id).then(response => {
          this.$message({
            type: 'success',
            message: '已删除',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      })
    },
    createRegion() {

      this.$refs['regionForm'].validate((valid) => {
        if (valid) {
          this.regionCreating = true;
          if (this.isEdit){
            let  id = this.newRegion.region_id;
            updateRegion(id,this.newRegion)
              .then(response => {
                this.$message({
                  message: '成功',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetNewRegion();
                this.dialogFormVisible = false;
                this.handleFilter();
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                this.regionCreating = false;
              });
          } else {
            createRegion(this.newRegion)
              .then(response => {
                this.$message({
                  message: '成功',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetNewRegion();
                this.dialogFormVisible = false;
                this.handleFilter();
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                this.regionCreating = false;
              });
          }
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetNewRegion() {
      this.newRegion = {
        name: '',
        parent_id:0,
        level:1,
        pinyin:'',
        show:10,
        sort: 100,
      };
    },
    handleSuccess(response, file, fileList){
      this.newRegion.image_id = response.file_id;
      this.newRegion.image_url = response.url;
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
