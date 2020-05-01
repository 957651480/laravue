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
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/parking/List'),
      name: 'ParkingList',
      meta: { title: '车位列表', icon: 'list',noCache: true },
    },
  ],
};

export default parkingRoutes;
