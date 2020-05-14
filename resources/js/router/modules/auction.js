/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const auctionRoutes = {
  path: '/auction',
  component: Layout,
  redirect: '/auction/list',
  name: 'Auction',
  meta: {
    title: '竞拍管理',
    icon: 'example',
    permissions: ['view menu auction ui'],
  },
  children: [
    {
      path: 'create',
      component: () => import('@/views/auction/Create'),
      name: 'CreateAuction',
      meta: { title: '创建竞拍', icon: 'edit' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/auction/Edit'),
      name: 'EditAuction',
      meta: { title: '编辑竞拍', noCache: true },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/auction/List'),
      name: 'AuctionList',
      meta: { title: '竞拍列表', icon: 'list',noCache: true },
    },
  ],
};

export default auctionRoutes;
