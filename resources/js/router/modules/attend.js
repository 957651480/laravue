/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const attendRoutes = {
  path: '/attend',
  component: Layout,
  redirect: '/attend/list',
  name: 'Attend',
  meta: {
    title: '报名管理',
    icon: 'example',
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/attend/List'),
      name: 'AttendList',
      meta: { title: '报名列表', icon: 'list' },
    },
  ],
};

export default attendRoutes;
