/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const parkingRoutes = {
  path: '/parking',
  component: Layout,
  redirect: '/parking/list',
  name: 'Parking',
  meta: {
    title: '车位管理',
    icon: 'example',
    permissions: ['view menu parking ui'],
  },
  children: [
    {
      path: 'create',
      component: () => import('@/views/parking/Create'),
      name: 'CreateParking',
      meta: { title: '创建车位', icon: 'edit' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/parking/Edit'),
      name: 'EditParking',
      meta: { title: '编辑车位', noCache: true },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/parking/List'),
      name: 'ParkingList',
      meta: { title: '车位列表', icon: 'list',noCache: true },
    },

    {
      path: 'area-list',
      component: () => import('@/views/parking-area/List'),
      name: 'ParkingAreaList',
      meta: { title: '区域列表', icon: 'list' },
    },
    {
      path: 'floor-list',
      component: () => import('@/views/parking-floor/List'),
      name: 'ParkingFloorList',
      meta: { title: '楼层列表', icon: 'list' },
    },
  ],
};

export default parkingRoutes;
