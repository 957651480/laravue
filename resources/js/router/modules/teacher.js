/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const teacherRoutes = {
  path: '/teacher',
  component: Layout,
  redirect: '/teacher/list',
  name: 'Teacher',
  meta: {
    title: '分类管理',
    icon: 'example',
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/teacher/List'),
      name: 'TeacherList',
      meta: { title: '教师列表', icon: 'list' },
    },
  ],
};

export default teacherRoutes;
