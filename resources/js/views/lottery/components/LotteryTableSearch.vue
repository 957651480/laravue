<template>
  <div class="app-container">
    <el-form :inline="true" style="margin-bottom: 20px">
      <el-input v-model="query.title" placeholder="请输入关键词" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />

      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
    </el-form>
    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.lottery_id }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="排序" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.sort }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="200px" label="标题">
        <template slot-scope="{row}">
          <router-link :to="'/lottery/edit/'+row.lottery_id" class="link-type">
            <span>{{ row.title }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="350">
        <template slot-scope="scope">
          <el-button type="success" size="small" icon="el-icon-check" @click="handleChoose(scope.row)">
            选择
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import waves from '@/directive/waves';

import { fetchList,deleteLottery } from '@/api/lottery';


export default {
    name: 'LotteryTableSearch',
    props: ['value'],
    components: { Pagination },
    directives: { waves },
    data() {
        return {
            list: null,
            total: 0,
            loading: false,
            query: {
                page: 1,
                limit: 15,
                keyword: '',
                category_id:''
            },
            categories:[],
        };
    },
    mounted() {
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
            this.total=data.total;
            this.loading = false;
        },
        handleFilter() {
            this.query.page = 1;
            this.getList();
        },
        handleChoose(item){
            this.$emit('input',item.lottery_id);
            this.$emit('updateLottery',item);
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
