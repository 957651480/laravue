<template>
  <parking-detail :is-edit="false" />
</template>

<script>
import ParkingDetail from './components/ParkingDetail';

export default {
  name: 'CreateForm',
  components: { ParkingDetail },
};
</script>

