<template>
  <div class="amap-page-container" :style="{ width: width, height: height }">
    <el-amap
      vid="amapDemo"
      :center="center"
      :zoom="zoom"
      class="amap-demo"
      :events="events">
    </el-amap>
    <div class="toolbar">
      position: [{{ lng }}, {{ lat }}] address: {{ address }}
    </div>
  </div>
</template>

<style>
  .amap-demo {
    height: 300px;
  }
</style>

<script>
export default {
  name:'GouldMap',
  props: {
    width: { type: String, default: '60%' },
    height: { type: String, default: '400px' },
    value: {
      type: Array,
      validator: (value) => {
        return value.length === 2
      }
    }
  },
  data: function() {
    let self = this;

    return {
      zoom: 12,
      center: this.value,
      address: '',
      events: {
        click(e) {
          let { lng, lat } = e.lnglat;
          self.lng = lng;
          self.lat = lat;

          // 这里通过高德 SDK 完成。
          var geocoder = new AMap.Geocoder({
            radius: 1000,
            extensions: "all"
          });
          geocoder.getAddress([lng ,lat], function(status, result) {
            if (status === 'complete' && result.info === 'OK') {
              if (result && result.regeocode) {
                self.address = result.regeocode.formattedAddress;
                self.$nextTick();
              }
            }
          });
        }
      },
      lng: 0,
      lat: 0
    };
  },
  mounted () {
    this.init(this.center)
  },
  methods:{
    init(point){
      // 地图实例对象 （amap 为容器的id）
      let amap = new AMap.Map('amap', {
        resizeEnable: true,
        zoom: 15
      });
      this.currentPosition(amap, point)
    },
    currentPosition (map, point) {
      if (point.lng!=='') {
        // 有传入坐标点，直接定位到坐标点
        map.setCenter(point)
        //this.addMark(map, point)

        // 获取地址
        //this.getAddress(point)
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
            this.center = points
            this.getAddress(points)
          } else {
            console.log('定位失败', result)
          }
        })
      }
    },
    // 添加标记
    addMark (map, points) {
      debugger
      let marker = new AMap.Marker({
        map: map,
        //position: points,
        draggable: true, // 允许拖动
        cursor: 'move',
        raiseOnDrag: true
      });
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
      this.$emit('location', this.point, this.address)
    },
  }
};
</script>
