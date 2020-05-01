import Vue from 'vue';

export default {
  install(Vue)  {
    Vue.prototype.arrayColumn = function (array,key){

      let tmpList = [];
      for (let i = 0;i < array.length;i++){
        tmpList[i]=array[i][key];
      }
      return tmpList;
    }
  }
}
