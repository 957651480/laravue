<template>
  <information-detail :is-edit="false" />
</template>

<script>
import InformationDetail from './components/InformationDetail';

export default {
  name: 'CreateForm',
  components: { InformationDetail },
};
</script>

