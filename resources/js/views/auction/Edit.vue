<template>
  <auction-detail :is-edit="true" />
</template>

<script>
import AuctionDetail from './components/AuctionDetail';

export default {
  name: 'EditForm',
  components: { AuctionDetail },
};
</script>

