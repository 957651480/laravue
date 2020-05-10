<template>
  <div class="app-container">
    <el-form :inline="true" >
      <el-form-item label="楼层名称:">
        <el-input v-model="query.name" placeholder="请输入楼层名称搜索" clearable style="width: 200px;" @change="handleFilter" class="filter-item" @keyup.enter.native="handleFilter" />
      </el-form-item>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-plus" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </el-form>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.parking_floor_id }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="排序" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.sort }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="名称">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="发布城市">
        <template slot-scope="scope">
          <span>{{ scope.row.city_name }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="作者">
        <template slot-scope="scope">
          <span>{{ scope.row.author_name }}</span>
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
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.parking_floor_id, scope.row.name);">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />

    <el-dialog v-model="isEdit" title="增加/编辑" :visible.sync="dialogFormVisible">
      <div v-loading="parking_floorCreating" class="form-container">
        <el-form ref="parking_floorForm" :rules="rules" :model="newParkingFloor" label-position="left" label-width="150px" style="max-width: 500px;">

          <el-form-item label="名称" prop="name">
            <el-input v-model="newParkingFloor.name" />
          </el-form-item>
          <el-form-item label="排序:" prop="sort">
            <el-input-number v-model="newParkingFloor.sort"></el-input-number>
            <span>排序越大越靠前</span>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false;isEdit=false">
            {{ $t('table.cancel') }}
          </el-button>
          <el-button type="primary" @click="createParkingFloor()">
            {{ $t('table.confirm') }}
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import { fetchList, updateParkingFloor, createParkingFloor, deleteParkingFloor } from '@/api/parking-floor';
import waves from '@/directive/waves';
export default {
  name: 'ParkingFloorList',
  components: { Pagination },
  directives: { waves },
  data() {
    return {
      list: null,
      total: 0,
      loading: true,
      downloading: false,
      parking_floorCreating: false,
      query: {
        page: 1,
        limit: 15,
        name: '',
      },
      newParkingFloor: {},
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
    this.resetParkingFloor();
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
      this.resetParkingFloor();
      this.dialogFormVisible = true;
      this.$nextTick(() => {
        this.$refs['parking_floorForm'].clearValidate();
      });
    },
    handleEdit(data){
      this.newParkingFloor = data;
      this.isEdit = true;
      this.dialogFormVisible = true;
    },
    handleDelete(id, name) {
      this.$confirm('确定删除分类名为 ' + name + '吗?', 'Warning', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        deleteParkingFloor(id).then(response => {
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
    createParkingFloor() {
      this.$refs['parking_floorForm'].validate((valid) => {
        if (valid) {
          this.parking_floorCreating = true;
          if (this.isEdit){
              let  parking_floor_id = this.newParkingFloor.parking_floor_id;
            updateParkingFloor(parking_floor_id,this.newParkingFloor)
              .then(response => {
                this.$message({
                  message: '成功',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetParkingFloor();
                this.dialogFormVisible = false;
                this.handleFilter();
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                this.parking_floorCreating = false;
              });
          } else {
            createParkingFloor(this.newParkingFloor)
              .then(response => {
                this.$message({
                  message: '成功',
                  type: 'success',
                  duration: 5 * 1000,
                });
                this.resetParkingFloor();
                this.dialogFormVisible = false;
                this.handleFilter();
              })
              .catch(error => {
                console.log(error);
              })
              .finally(() => {
                this.parking_floorCreating = false;
              });
          }
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetParkingFloor() {
      this.newParkingFloor = {
        parking_floor_id:null,
        name: '',
        sort: 0,
      };
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
