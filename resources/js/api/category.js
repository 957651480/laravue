import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'categories',
    method: 'get',
    params: query,
  });
}

export function fetchCategory(id) {
  return request({
    url: 'categories/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: 'categories/' + id + '/pageviews',
    method: 'get',
  });
}

export function createCategory(data) {
  return request({
    url: 'categories',
    method: 'post',
    data,
  });
}

export function updateCategory(id,data) {
  return request({
    url: 'categories/'+id,
    method: 'put',
    data,
  });
}

export function deleteCategory(id) {
  return request({
    url: 'categories/' + id,
    method: 'delete',
  });
}
