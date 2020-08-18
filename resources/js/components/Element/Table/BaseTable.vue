<template>
    <div v-bind="$attrs" class="base-table">
        <el-table
                :data="data"
                :ref="$attrs.ref"
                v-bind="tableAttrs"
                v-on="$listeners"
        >
            <!--region 数据列-->
            <template v-for="(column, index) in columns">

                <!-- slot 添加自定义配置项 -->
                <slot  v-if="column.slot"  :name="column.slot"></slot>

                <!-- 默认渲染列 -->
                <el-table-column v-else :prop="column.prop"
                                 :key='column.label'
                                 :label="column.label"
                                 :align="column.align"
                                 :width="column.width"
                                 :show-overflow-tooltip="true">
                    <template slot-scope="scope">
                        <template v-if="!column.render">
                            <template v-if="column.formatter">
                                <span v-html="column.formatter(scope.row, column)"></span>
                            </template>
                            <template v-else>
                                <span>{{scope.row[column.prop]}}</span>
                            </template>
                        </template>
                        <template v-else>
                            <expand-dom :column="column" :row="scope.row" :render="column.render" :index="index"></expand-dom>
                        </template>
                    </template>
                </el-table-column>

            </template>
            <!--endregion-->
        </el-table>
    </div>
</template>
<script>
    import {defaultColumn, defaultTableAttrs} from './tableConfig'

    export default {
        name: 'BaseTable',
        components: {
            expandDom: {
                functional: true,
                props: {
                    row: Object,
                    col: Object,
                    render: Function,
                    colIndex: [Number, String]
                },
                render(h, ctx) {
                    const randomIndex = Math.random().toString(35).replace('.', '')
                    const params = {
                        row: { ...ctx.props.row },
                        colIndex: ctx.props.colIndex || randomIndex
                    }
                    if (ctx.props.col) {
                        params.col = ctx.props.col
                    }
                    return ctx.props.render && ctx.props.render(h, params)
                }
            }
        },
        props: {
            data: {
                type: Array,
                default() {
                    return []
                }
            },
            columns: {
                type: Array,
                default() {
                    return []
                }
            },

        },
        data() {
            return {
                tableAttrs: {},
                columnAttrs: [],
            }
        },
        created() {
            this.$nextTick(() => {
                this.init()
            })
        },
        methods: {
            init() {
                // 获取element table上的属性
                const tableAttrs = { }
                Object.keys(defaultTableAttrs).forEach(key => {
                    if (this.$attrs[key] !== undefined) {
                        tableAttrs[key] = this.$attrs[key]
                    }
                })
                this.tableAttrs = Object.assign({}, defaultTableAttrs, tableAttrs)

                // 获取element table col上的属性
                this.columnAttrs = this.columns.map(column => {
                    const obj = Object.assign({}, defaultColumn, column)
                    return obj
                })
            },
        }
    }
</script>




