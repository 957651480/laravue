<template>
  <information-detail :is-edit="true" />
</template>

<script>
import InformationDetail from './components/InformationDetail';

export default {
  name: 'EditForm',
  components: { InformationDetail },
};
</script>

