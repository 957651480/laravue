<template>
  <house-detail :is-edit="true" />
</template>

<script>
import HouseDetail from './components/HouseDetail';

export default {
  name: 'EditForm',
  components: { HouseDetail },
};
</script>

