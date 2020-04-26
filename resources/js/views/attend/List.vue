<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="query.keyword" placeholder="请输入关键词" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
    </div>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.attend_id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="报名用户微信用户名">
        <template slot-scope="scope">
          <span>{{ scope.row.user_nickName }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="年级">
        <template slot-scope="scope">
          <span>{{ scope.row.grade }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="班级">
        <template slot-scope="scope">
          <span>{{ scope.row.class }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="姓名">
        <template slot-scope="scope">
          <span>{{ scope.row.student_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="报名的课程">
        <template slot-scope="scope">
          <span>{{ scope.row.course_title }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="报名的课程">
        <template slot-scope="scope">
          <span>{{ scope.row.course_title }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="时间段">
        <template slot-scope="scope">
          <div v-for="item in scope.row.times" >
            <span>开始时间:{{ item.start_time  }}</span>
            <span>结束时间:{{ item.end_time }}</span>
          </div>

        </template>
      </el-table-column>

      <el-table-column align="center" label="报名时间">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="350">
        <template slot-scope="scope">
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.attend_id)">
            删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import waves from '@/directive/waves'; // Waves directive
import { fetchList, deleteAttend } from '@/api/attend';


export default {
  name: 'AttendList',
  components: { Pagination },
  directives: { waves },
  data() {
    return {
      list: null,
      total: 0,
      loading: true,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
      },
    };
  },
  computed: {
  },
  created() {
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

    handleDelete(id) {
      this.$confirm('确定删除吗?', 'Warning', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        deleteAttend(id).then(response => {
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
