<template>
  <div class="map-box" :style="{ width: width, height: height }">

    <div id="amap" class="amap">
    </div>
    <div class="detail">
      <p>经度：{{point ? point[0] : '-'}}</p>
      <p>纬度：{{point ? point[1] : '-'}}</p>
      <p>地址：{{address}}</p>
      <button size="mini" class="btnmap" @click="commit">确定</button>
    </div>
  </div>

</template>

<script>
    import AMap from 'AMap'
    export default {
        name: "GouldMap",
        props: {
            width: { type: String, default: '80%' },
            height: { type: String, default: '400px' },
            value: {
                type: Array,
            }
        },
        data () {
            return { address: '', point: this.value }
        },
        mounted () {
            this.init(this.point)
        },
        methods: {

            // 初始化
            init (lnglat) {
                debugger
                // 地图实例对象 （amap 为容器的id）
                let amap = new AMap.Map('amap', {
                    resizeEnable: true,
                    zoom: 15
                })

                // 注入插件（定位插件，地理编码插件）
                amap.plugin(['AMap.Geolocation', 'AMap.Geocoder'])

                // 定位
                this.currentPosition(amap, lnglat)
            },

            currentPosition (map, lnglat) {
                if (lnglat.length>0) {
                    // 有传入坐标点，直接定位到坐标点
                    map.setCenter(lnglat)
                    this.addMark(map, lnglat)

                    // 获取地址
                    this.getAddress(lnglat)
                } else {
                    // 没有传入坐标点，则定位到当前所在位置
                    let geolocation = new AMap.Geolocation({
                        enableHighAccuracy: true,
                        timeout: 10000,
                        zoomToAccuracy: true,
                        buttonPosition: 'RB'
                    })
                    geolocation.getCurrentPosition((status, result) => {
                        if (status === 'complete') {
                            let points = [result.position.lng, result.position.lat]
                            map.setCenter(points) // 设置中心点
                            this.addMark(map, points) // 添加标记

                            // 存下坐标与地址
                            this.point = points
                            this.getAddress(points)
                        } else {
                            console.log('定位失败', result)
                        }
                    })
                }
            },

            // 添加标记
            addMark (map, points) {
                let marker = new AMap.Marker({
                    map: map,
                    position: points,
                    draggable: true, // 允许拖动
                    cursor: 'move',
                    raiseOnDrag: true
                })
                marker.on('dragend', (e) => {
                    let position = marker.getPosition()

                    // 存下坐标与地址
                    this.point = [position.lng, position.lat]
                    this.getAddress([position.lng, position.lat])
                })
            },

            // 根据坐标返回地址(逆地理编码)
            getAddress (points) {
                let geocoder = new AMap.Geocoder({ radius: 1000 })
                geocoder.getAddress(points, (status, result) => {
                    if (status === 'complete' && result.regeocode) {
                        this.address = result.regeocode.formattedAddress
                    }
                })
            },

            commit () {
                this.$emit('input', this.point);
                return false;
            },
        }
    }
</script>

<style scoped>
  .map-box {
    box-sizing: border-box;
    background-color: #ddd;
    padding: 15px;
  }
  :after {
    content: '';
    display: block;
    clear: both;
  }
  .amap, .detail {
    float: left;
    height: 100%;
  }
  .amap {
    width: 75%;
  }
  .detail {
    width: 25%;
    background-color: #fff;
    padding: 0 15px;
    border-left: 1px solid #eee;
    box-sizing: border-box;
    word-wrap: break-word;
  }
  .btnmap {
    width: 100%;
    margin: 30px 0 0 0;
    padding: 5px 0;
    color: #fff;
    cursor: pointer;
    background-color: #409eff;
    border: none;
    border-radius: 3px;
  }


</style>
