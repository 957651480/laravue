<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input v-model="query.keyword" placeholder="请输入关键词" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
      <el-select v-model="query.category_id" placeholder="选择分类" clearable style="width: 90px" class="filter-item" @change="handleFilter">
        <el-option v-for="item in categories" :key="item.category_id" :label="item.name | uppercaseFirst" :value="item.category_id" />
      </el-select>

      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
      <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        {{ $t('table.export') }}
      </el-button>
    </div>
    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.course_id }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="200px" label="标题">
        <template slot-scope="{row}">
          <router-link :to="'/course/edit/'+row.course_id" class="link-type">
            <span>{{ row.title }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="分类">
        <template slot-scope="{row}">
            <span>{{ row.category_name }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="100px" label="课程教师">
        <template slot-scope="{row}">
          <span>{{ row.teacher_name }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="80px" label="报名人数">
        <template slot-scope="scope">
          <span>{{ scope.row.attend_number }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="80px" label="课程限定人数">
        <template slot-scope="scope">
            <span>{{ scope.row.number }}</span>
        </template>
      </el-table-column>
      <el-table-column min-width="200px" label="图片">
        <template slot-scope="scope">
    　　　　<img :src="scope.row.image_url" width="40" height="40" />
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="开始时间">
        <template slot-scope="scope">
          <span>{{ scope.row.start_time | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="结束时间">
        <template slot-scope="scope">
          <span>{{ scope.row.end_time | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="地址">
        <template slot-scope="scope">
          <span>{{ scope.row.address }}</span>
        </template>
      </el-table-column>
      <!--<el-table-column width="180px" align="center" label="Date">
        <template slot-scope="scope">
          <span>{{ scope.row.timestamp | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>

      <el-table-column width="120px" align="center" label="Author">
        <template slot-scope="scope">
          <span>{{ scope.row.author }}</span>
        </template>
      </el-table-column>

      <el-table-column width="100px" label="Importance">
        <template slot-scope="scope">
          <svg-icon v-for="n in +scope.row.importance" :key="n" icon-class="star" class="meta-item__icon" />
        </template>
      </el-table-column>

      <el-table-column class-name="status-col" label="Status" width="110">
        <template slot-scope="{row}">
          <el-tag :type="row.status | statusFilter">
            {{ row.status }}
          </el-tag>
        </template>
      </el-table-column>-->



      <el-table-column align="center" label="操作" width="350">
        <template slot-scope="scope">
          <router-link :to="'/course/edit/'+scope.row.course_id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              编辑
            </el-button>
          </router-link>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.course_id)">
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
import Resource from '@/api/resource';
import waves from '@/directive/waves';
const courseResource = new Resource('courses');
const categoryResource = new Resource('categories');

export default {
  name: 'CourseList',
  components: { Pagination },
  directives: { waves },
  data() {
    return {
      list: null,
      total: 0,
      downloading: false,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        category_id:''
      },
      categories:[]
    };
  },
  created() {
    this.getList();
    this.getCategory();
  },
  methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data } = await courseResource.list(this.query);
      this.list = data.list;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total=data.total;
      this.loading = false;
    },
    async getCategory() {
      const { data } = await categoryResource.list(this.query);
      this.categories = data.list;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleDelete(id) {
      courseResource.destroy(id).then(response => {
          this.$message({
            type: 'success',
            message: '已删除',
          });
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });

    },
    handleDownload() {
      this.downloading = true;
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['id', 'user_id', 'name', 'email', 'role'];
        const filterVal = ['index', 'id', 'name', 'email', 'role'];
        const data = this.formatJson(filterVal, this.list);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'user-list',
        });
        this.downloading = false;
      });
    },
  },
};
</script>

<style scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
</style>
