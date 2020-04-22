/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const courseRoutes = {
  path: '/course',
  component: Layout,
  redirect: '/course/list',
  name: 'Example',
  meta: {
    title: '课程管理',
    icon: 'example',
  },
  children: [
    {
      path: 'create',
      component: () => import('@/views/courses/Create'),
      name: 'CreateCourse',
      meta: { title: '创建课程', icon: 'edit' },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/courses/Edit'),
      name: 'EditCourse',
      meta: { title: '编辑课程', noCache: true },
      hidden: true,
    },
    {
      path: 'list',
      component: () => import('@/views/courses/List'),
      name: 'CourseList',
      meta: { title: '课程列表', icon: 'list' },
    },
  ],
};

export default courseRoutes;
