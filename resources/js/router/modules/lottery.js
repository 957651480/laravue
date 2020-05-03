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
      path: 'list',
      component: () => import('@/views/lottery/List'),
      name: 'LotteryList',
      meta: { title: '转盘列表', icon: 'list' },
    },
  ],
};

export default lotteryRoutes;
