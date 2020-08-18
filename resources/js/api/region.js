import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'admin/region',
    method: 'get',
    params: query,
  });
}

export function fetchTreeList(query={}) {
  return request({
    url: 'admin/region/tree_list',
    method: 'get',
    params: query,
  });
}

export function fetchRegion(id) {
  return request({
    url: 'admin/region/detail/' + id,
    method: 'get',
  });
}


export function createRegion(data) {
  return request({
    url: 'admin/region/create',
    method: 'post',
    data,
  });
}

export function updateRegion(id,data) {
  return request({
    url: 'admin/region/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteRegion(id) {
  return request({
    url: 'admin/region/delete/' + id,
    method: 'get',
  });
}
