<template>
  <auction-detail :is-edit="false" />
</template>

<script>
import AuctionDetail from './components/AuctionDetail';

export default {
  name: 'CreateForm',
  components: { AuctionDetail },
};
</script>

