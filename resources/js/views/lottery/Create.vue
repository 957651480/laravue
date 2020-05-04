<template>
  <lottery-detail :is-edit="false" />
</template>

<script>
import LotteryDetail from './components/LotteryDetail';

export default {
  name: 'CreateForm',
  components: { LotteryDetail },
};
</script>

