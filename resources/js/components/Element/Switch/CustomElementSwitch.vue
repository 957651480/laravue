<template>
    <el-switch
        v-model="elValue"
        active-color="#13ce66"
        inactive-color="#ff4949"
        :active-value="10"
        :inactive-value="20"
        @change="handleChange()"
    >
    </el-switch>
</template>

<script>
export default {
    name: "CustomElementSwitch",
    props:{
        value: {
            type: [Boolean, String, Number],
            default: false
        },
    },
    data(){
      return{

      }
    },
    computed: {
        elValue: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val)
            }
        }
    },
    methods:{
        handleChange(value){
            return this.$emit('change', value);
        }
    }
}
</script>

<style scoped>

</style>
