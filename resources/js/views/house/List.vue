<template>
  <div class="app-container">
    <el-form :inline="true" >
      <el-form-item label="楼盘名称:">
        <el-input v-model="query.name" placeholder="请输入楼盘名称" clearable style="width: 200px;" @change="handleFilter" class="filter-item" @keyup.enter.native="handleFilter" />
      </el-form-item>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        {{ $t('table.search') }}
      </el-button>
      <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        {{ $t('table.export') }}
      </el-button>
    </el-form>

    <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.house_id }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="排序" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.sort }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="200px" label="楼盘名称">
        <template slot-scope="{row}">
          <router-link :to="'/house/edit/'+row.house_id" class="link-type">
            <span>{{ row.name }}</span>
          </router-link>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="100px" label="车位数">
        <template slot-scope="{row}">
            <span>{{ row.parking_count }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="100px" label="车位均价">
        <template slot-scope="{row}">
          <span>{{ row.parking_avg }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="100px" label="预约数">
        <template slot-scope="{row}">
          <span>{{ row.appoint_count }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="200px"  label="楼盘图片" >
        <template slot-scope="scope">
          <el-image
            style="width: 80px; height: 80px"
            :src="scope.row.image_list[0].url"
            :preview-src-list="showImageList(scope.row.image_list)"
            ></el-image>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="80px" label="住户数">
        <template slot-scope="scope">
          <span>{{ scope.row.household }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="80px" label="推荐楼盘">
        <template slot-scope="scope">
          <span>{{scope.row.house_recommend===10?'是':'否' }}</span>
        </template>
      </el-table-column>
      <el-table-column width="180px" align="center" label="区域">
        <template slot-scope="scope">
          <span>{{ scope.row.region_merger_name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" min-width="80px" label="发布时间">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="操作" width="350">
        <template slot-scope="scope">
          <router-link :to="'/house/edit/'+scope.row.house_id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              编辑
            </el-button>
          </router-link>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.house_id)">
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

import { fetchList,deleteHouse } from '@/api/house';

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
        name: '',
      },
      categories:[],
    };
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
      this.total=data.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    handleDelete(id) {
      deleteHouse(id).then(response => {
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

        axios
            .get("/api/courses/export", {
                params: this.query,
                headers:this.myHeaders,
                responseType : "blob" // 1.首先设置responseType对象格式为 blob:
            })
            .then(
                res => {
                    //resolve(res)
                    let blob = new Blob([res.data], {
                        type: "application/vnd.ms-excel"
                    }); // 2.获取请求返回的response对象中的blob 设置文件类型，这里以excel为例
                    let url = window.URL.createObjectURL(blob); // 3.创建一个临时的url指向blob对象

                    // 4.创建url之后可以模拟对此文件对象的一系列操作，例如：预览、下载
                    let a = document.createElement("a");
                    a.href = url;
                    a.download = "课程.csv";
                    a.click();
                    // 5.释放这个临时的对象url
                    window.URL.revokeObjectURL(url);
                    this.$message({
                        type: 'success',
                        message: '已导出',
                    });
                    this.downloading = false;
                },
                err => {
                    resolve(err.response);
                }
            )
            .catch(error => {
                reject(error);
            });
    },
    showImageList(imageList){
        let tmpList = [];
        for (let i = 0;i < imageList.length;i++){
            tmpList[i]=imageList[i].url;
        }
        return tmpList;
    }
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
