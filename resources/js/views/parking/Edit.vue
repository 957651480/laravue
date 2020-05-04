<template>
  <parking-detail :is-edit="true" />
</template>

<script>
import ParkingDetail from './components/ParkingDetail';

export default {
  name: 'EditForm',
  components: { ParkingDetail },
};
</script>

