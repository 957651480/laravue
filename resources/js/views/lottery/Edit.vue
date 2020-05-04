<template>
  <lottery-detail :is-edit="true" />
</template>

<script>
import LotteryDetail from './components/LotteryDetail';

export default {
  name: 'EditForm',
  components: { LotteryDetail },
};
</script>

