<template>
  <lottery-prize-detail :is-edit="true" />
</template>

<script>
import LotteryPrizeDetail from './components/LotteryPrizeDetail';

export default {
  name: 'EditForm',
  components: { LotteryPrizeDetail },
};
</script>

