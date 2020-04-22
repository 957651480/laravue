<template>
  <article-detail :is-edit="false" />
</template>

<script>
import ArticleDetail from './components/CourseDetail';

export default {
  name: 'CreateForm',
  components: { ArticleDetail },
};
</script>

