/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const regionRoutes = {
  path: '/region',
  component: Layout,
  redirect: '/region/list',
  name: 'Region',
  meta: {
    title: '分类管理',
    icon: 'example',
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/region/List'),
      name: 'RegionList',
      meta: { title: '地区列表', icon: 'list' },
    },
  ],
};

export default regionRoutes;
