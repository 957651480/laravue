<template>
  <house-detail :is-edit="false" />
</template>

<script>
import HouseDetail from './components/HouseDetail';

export default {
  name: 'CreateForm',
  components: { HouseDetail },
};
</script>

