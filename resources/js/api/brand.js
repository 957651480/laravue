import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'admin/brand',
    method: 'get',
    params: query,
  });
}

export function fetchBrand(id) {
  return request({
    url: 'admin/brand/detail/' + id,
    method: 'get',
  });
}


export function createBrand(data) {
  return request({
    url: 'admin/brand/create',
    method: 'post',
    data,
  });
}

export function updateBrand(id,data) {
  return request({
    url: 'admin/brand/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteBrand(id) {
  return request({
    url: 'admin/brand/delete/' + id,
    method: 'get',
  });
}
export function batchDeleteBrand(data) {

  return request({
    url: 'admin/brand/batch/delete',
    method: 'post',
    data
  })
}