/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const categoryRoutes = {
  path: '/category',
  component: Layout,
  redirect: '/category/list',
  name: 'Category',
  meta: {
    title: '分类管理',
    icon: 'example',
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/categories/List'),
      name: 'CategoryList',
      meta: { title: '分类列表', icon: 'list' },
    },
  ],
};

export default categoryRoutes;
