/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const lotteryRoutes = {
  path: '/lottery',
  component: Layout,
  redirect: '/lottery/list',
  name: 'Lottery',
  meta: {
    title: '转盘管理',
    icon: 'example',
  },
  children: [
    {
      path: 'create',
      component: () => import('@/views/lottery/Create'),
      name: 'CreateLottery',
      meta: { title: '创建转盘', icon: 'edit' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/lottery/Edit'),
      name: 'EditLottery',
      meta: { title: '编辑转盘', noCache: true },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/lottery/List'),
      name: 'LotteryList',
      meta: { title: '转盘列表', icon: 'list',noCache: true },
    },
  ],
};

export default lotteryRoutes;
