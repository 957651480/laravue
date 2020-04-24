import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'courses',
    method: 'get',
    params: query,
  });
}

export function fetchCourse(id) {
  return request({
    url: 'courses/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: 'course/' + id + '/pageviews',
    method: 'get',
  });
}

export function createCourse(data) {
  return request({
    url: 'courses',
    method: 'post',
    data,
  });
}

export function updateCourse(data) {
  return request({
    url: 'courses/update',
    method: 'put',
    data,
  });
}

export function deleteCourse(id) {
  return request({
    url: 'courses/' + id,
    method: 'delete',
  });
}

export function exportCourse() {
  return request({
    url: 'courses/export',
    method: 'get',
  });
}
