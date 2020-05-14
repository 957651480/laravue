/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const houseRoutes = {
  path: '/house',
  component: Layout,
  redirect: '/house/list',
  name: 'House',
  meta: {
    title: '楼盘管理',
    icon: 'example',
    permissions: ['view menu house ui'],
  },
  children: [
    {
      path: 'create',
      component: () => import('@/views/house/Create'),
      name: 'CreateHouse',
      meta: { title: '创建楼盘', icon: 'edit' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/house/Edit'),
      name: 'EditHouse',
      meta: { title: '编辑楼盘', noCache: true },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/house/List'),
      name: 'HouseList',
      meta: { title: '楼盘列表', icon: 'list',noCache: true },
    },
  ],
};

export default houseRoutes;
