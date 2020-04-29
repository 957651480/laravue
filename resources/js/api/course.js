import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'courses/admin/list',
    method: 'get',
    params: query,
  });
}

export function fetchCourse(id) {
  return request({
    url: 'courses/admin/detail/' + id,
    method: 'get',
  });
}


export function createCourse(data) {
  return request({
    url: 'courses/create',
    method: 'post',
    data,
  });
}

export function updateCourse(id,data) {
  return request({
    url: 'courses/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteCourse(id) {
  return request({
    url: 'courses/delete/' + id,
    method: 'get',
  });
}

export function exportCourse() {
  return request({
    url: 'courses/export',
    method: 'get',
    responseType: "blob"
  });
}
