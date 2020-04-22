/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const categoryRoutes = {
  path: '/category',
  component: Layout,
  redirect: '/category/list',
  name: 'Example',
  meta: {
    title: '分类管理',
    icon: 'example',
  },
  children: [
    {
      path: 'create',
      component: () => import('@/views/categories/Create'),
      name: 'CreateCategory',
      meta: { title: '创建分类', icon: 'edit' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/categories/Edit'),
      name: 'EditCategory',
      meta: { title: '编辑分类', noCache: true },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/categories/List'),
      name: 'CategoryList',
      meta: { title: '分类列表', icon: 'list' },
    },
  ],
};

export default categoryRoutes;
