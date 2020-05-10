<template>
  <lottery-prize-detail :is-edit="false" />
</template>

<script>
import LotteryPrizeDetail from './components/LotteryPrizeDetail';

export default {
  name: 'CreateForm',
  components: { LotteryPrizeDetail },
};
</script>

