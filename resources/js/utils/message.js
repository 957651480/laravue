import Vue from "vue";

export function message(options) {
    Vue.prototype.$message(options)
}
export function httpSuccess(response={}) {
    Vue.prototype.$message({
        message: response.msg,
        type: 'success',
        duration: 1500
    })
}

export function confirmMessage(message,title='提示',...args) {
    let _default = {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
    }
    return Vue.prototype.$confirm(message, title, Object.assign(_default,args))
}
