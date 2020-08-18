<template>
  <div class="app-container">
    <el-form :inline="true" >
      <el-form-item label="轮播标题:">
        <el-input v-model="query.title" placeholder="请输入轮播标题" clearable style="width: 200px;" @change="handleFilter" class="filter-item" @keyup.enter.native="handleFilter" />
      </el-form-item>
      <el-button v-waves type="primary" icon="el-icon-search" @click="handleFilter">
        搜索
      </el-button>
      <el-button type="primary" icon="el-icon-plus" @click="handleCreate">
        添加
      </el-button>
      <el-button v-waves :loading="downloading" class="filter-item" type="primary" icon="el-icon-download" @click="handleDownload">
        导出
      </el-button>
    </el-form>

    <base-table :data="list" :columns="columns" ref="table" v-loading="tableLoading"  border fit highlight-current-row style="width: 100%"
                @selection-change="handleSelectionChange">

      <el-table-column  type="selection" style="width: 55px;" slot="select">
      </el-table-column>
      <el-table-column align="center" label="图片" slot="image_url" >
        <template slot-scope="scope">
          <el-image
                  style="width: 80px; height: 80px"
                  :src="scope.row.image_url"
                  :preview-src-list="[scope.row.image_url]"
          ></el-image>
        </template>
      </el-table-column>
      <el-table-column align="center" label="显示" slot="image_url">
        <template slot-scope="scope">
          <custom-element-switch v-model="scope.row.show" @change="handleChange(scope.row)"></custom-element-switch>
        </template>
      </el-table-column>

      <el-table-column align="center" label="操作" width="350" slot="operate">
        <template slot-scope="scope">
          <el-button type="primary" size="small" icon="el-icon-edit" @click="handleEdit(scope.$index,scope.row)">
            编辑
          </el-button>
          <el-button type="primary" size="small" icon="el-icon-copy-document" @click="handleCopy(scope.$index,scope.row)">
            复制
          </el-button>
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.$index,scope.row)">
            删除
          </el-button>
        </template>
      </el-table-column>

    </base-table>
    <el-row >
      <el-col :span="12" style="float: left;padding-top: 20px">

        <el-button type="primary" size="small"  @click="toggleSelection(true)">
          全选
        </el-button>
        <el-button type="primary" size="small"  @click="toggleSelection(false)">
          取消
        </el-button>
        <el-button type="danger" size="small" icon="el-icon-delete" :disabled="batchDisabled" @click="handleBatchDelete()">
          批量删除
        </el-button>
      </el-col>
    </el-row>
    <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
    <el-dialog v-model="isEdit" :title="isEdit?'编辑':'添加'" :visible.sync="dialogFormVisible" @close='closeDialog'>
      <div v-loading="BannerCreating" class="form-container">
        <el-form ref="userForm" :rules="rules" :model="newBanner" label-position="left" label-width="150px" style="max-width: 500px;">
          <el-form-item label="标题:" prop="title">
            <el-input v-model="newBanner.title" show-word-limit maxlength="25"/>
          </el-form-item>
          <el-form-item  label="简介:">
            <el-input v-model="newBanner.desc"  type="textarea"  placeholder="请输入简介" />
          </el-form-item>
          <el-form-item label="图片:" prop="image_id">
            <single-upload v-model="newBanner.image_id" :img_url.sync="newBanner.image_url"></single-upload>
          </el-form-item>
          <el-form-item label="状态:" prop="show">
            <custom-element-switch v-model="newBanner.show" ></custom-element-switch>
          </el-form-item>

          <el-form-item label="排序:" prop="sort">
            <el-input-number
                    v-model="newBanner.sort"
                    controls-position="right"
                    :min="0" :max="100000"
                    placeholder="排序越大越靠前"
            >
            </el-input-number>
            <span>(排序越大越靠前)</span>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button type="primary" @click="saveBanner()">
            保存
          </el-button>
          <el-button @click="closeDialog()">
            取消
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
  import waves from '@/directive/waves'; // Waves directive
  import {batchDeleteBanner, createBanner, deleteBanner, fetchList, updateBanner} from '@/api/banner';
  import SingleUpload from "@/components/Upload/SingleUpload";
  import {confirmMessage, httpSuccess} from "@/utils/message";
  import CustomElementSwitch from "@/components/Element/Switch/CustomElementSwitch";
  import BaseTable from "@/components/Element/Table/BaseTable";

  export default {
    name: 'BannerList',
    components: {BaseTable, CustomElementSwitch, SingleUpload,  Pagination },
    directives: { waves },
    data() {
      return {
        list: null,
        total: 0,
        tableLoading: true,
        downloading: false,
        BannerCreating: false,
        query: {
          page: 1,
          limit: 15,
          title: '',
        },
        newBanner: {},
        dialogFormVisible: false,
        rules: {
          title: [{ required: true, message: '标题必须', trigger: 'blur' }],
          image_id: [{ required: true, message: '图片必须', trigger: 'blur' }],
        },
        isEdit: false,
        multipleSelection: [],
        columns:[
          {slot: 'select'},
          {
            prop: "id",
            width:"80",
            align:"center",
            label: "ID"
          },
          {
            prop: "sort",
            width:"80",
            align:"center",
            label: "排序"
          },
          {
            prop: "title",
            align:"center",
            label: "标题"
          },

          {
            align:"center",
            slot:'image_url'
          },
          {
            align:"center",
            slot:'show'
          },
          {
            align:"center",
            prop: "created_at",
            label: "创建时间"
          },
          {
            align:"center",
            slot:'operate'
          }
        ],

      };
    },
    computed: {
      batchDisabled:function() {
        return this.multipleSelection.length === 0
      },
    },
    created() {
      this.resetNewBanner();
      this.getList();
    },
    methods: {
      async getList() {
        this.tableLoading = true;
        const { total,data } = await fetchList(this.query);
        this.list = data;
        this.total = total;
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
      handleEdit(index,row){
        debugger
        this.newBanner = row;
        this.isEdit = true;
        this.dialogFormVisible = true;
      },
      handleCopy(index,row){
        this.newBanner = row;
        this.createBanner();
      },
      handleDelete(index,row) {
        confirmMessage('确定删除吗?').then(() => {
          deleteBanner(row.id).then(response => {
            httpSuccess(response);
            this.handleFilter();
          }).catch(error => {
            console.log(error);
          });
        }).catch(() => {
        });
      },
      createBanner(){
        createBanner(this.newBanner)
                .then(response => {
                  httpSuccess(response);
                  this.dialogFormVisible = false;
                  this.handleFilter();
                })
                .catch(error => {
                  console.log(error);
                })
                .finally(() => {
                  this.BannerCreating = false;
                });
      },
      updateBanner(){
        let  id = this.newBanner.id;
        updateBanner(id,this.newBanner)
                .then(response => {
                  httpSuccess(response);
                  this.dialogFormVisible = false;
                })
                .catch(error => {
                  console.log(error);
                })
                .finally(() => {
                  this.BannerCreating = false;
                });
      },
      saveBanner() {
        this.$refs['userForm'].validate((valid) => {
          if (!valid) return false;
          this.BannerCreating = true;
          this.isEdit?this.updateBanner():this.createBanner()
        });
      },
      resetNewBanner() {

        this.newBanner = {
          title: '',
          desc: '',
          image_id:null,
          image_url:null,
          show: 10,
          sort: 0,
        };
      },
      showImageList(imageList){
        let tmpList = [];
        for (let i = 0;i < imageList.length;i++){
          tmpList[i]=imageList[i].url;
        }
        return tmpList;
      },
      handleDownload(){

      },
      closeDialog(){
        this.isEdit=false;
        this.dialogFormVisible = false;
      },
      handleBatchDelete(){

        let ids=[];
        this.multipleSelection.forEach(row=>{
          ids.push(row.id)
        });
        batchDeleteBanner({ids:ids}).then(response => {
          httpSuccess(response);
          this.handleFilter();
        }).catch(error => {
          console.log(error);
        });
      },

      toggleSelection(check)
      {
        if (check) {
          this.list.forEach(row => {
            this.$refs.table.toggleRowSelection(row);
          });
        } else {
          this.$refs.table.clearSelection();
        }
      },
      handleSelectionChange(val) {
        this.multipleSelection = val;
      },
      handleChange(data){
        this.newBanner = data;
        this.updateBanner();
      },
    },
  };
</script>

<style lang="scss" scoped>

</style>
