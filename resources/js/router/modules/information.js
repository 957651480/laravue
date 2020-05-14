/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const informationRoutes = {
  path: '/information',
  component: Layout,
  redirect: '/information/list',
  name: 'Information',
  meta: {
    title: '资讯管理',
    icon: 'example',
    permissions: ['view menu information ui'],
  },
  children: [
    {
      path: 'create',
      component: () => import('@/views/information/Create'),
      name: 'CreateInformation',
      meta: { title: '创建资讯', icon: 'edit' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/information/Edit'),
      name: 'EditInformation',
      meta: { title: '编辑资讯', noCache: true },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/information/List'),
      name: 'InformationList',
      meta: { title: '资讯列表', icon: 'list',noCache: true },
    },
  ],
};

export default informationRoutes;
