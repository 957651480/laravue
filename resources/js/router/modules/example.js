/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const exampleRoutes = {
  path: '/example',
  component: Layout,
  redirect: '/example/list',
  name: 'Example',
  hidden: true,
  meta: {
    title: 'example',
    icon: 'example',
  },
  children: [
    {
      path: 'create',
      component: () => import('@/views/articles/Create'),
      name: 'CreateArticle',
      meta: { title: 'createArticle', icon: 'edit' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/articles/Edit'),
      name: 'EditArticle',
      meta: { title: 'editArticle', noCache: true },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/articles/List'),
      name: 'ArticleList',
      meta: { title: 'articleList', icon: 'list' },
    },
  ],
};

export default exampleRoutes;
