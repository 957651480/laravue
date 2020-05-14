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
    permissions: ['view menu lottery ui'],
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
    {
      path: '/lottery/prize/create',
      component: () => import('@/views/lottery-prize/Create'),
      name: 'CreateLotteryPrize',
      meta: { title: '创建奖项', icon: 'edit' },
    },
    {
      path: '/lottery/prize/edit/:id(\\d+)',
      component: () => import('@/views/lottery-prize/Edit'),
      name: 'EditLotteryPrize',
      meta: { title: '编辑奖项', noCache: true },
      hidden: true,
    },
    {
      path: '/lottery/prize/list',
      component: () => import('@/views/lottery-prize/List'),
      name: 'LotteryPrizeList',
      meta: { title: '奖项列表', icon: 'list',noCache: true },
    },
    {
      path: '/lottery/record/list',
      component: () => import('@/views/lottery-prize/List'),
      name: 'LotteryRecordList',
      meta: { title: '中奖记录', icon: 'list',noCache: true },
    },
  ],
};

export default lotteryRoutes;
