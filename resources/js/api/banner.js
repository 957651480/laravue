import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'admin/banner',
    method: 'get',
    params: query,
  });
}

export function fetchBanner(id) {
  return request({
    url: 'admin/banner/detail/' + id,
    method: 'get',
  });
}


export function createBanner(data) {
  return request({
    url: 'admin/banner/create',
    method: 'post',
    data,
  });
}

export function updateBanner(id,data) {
  return request({
    url: 'admin/banner/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteBanner(id) {
  return request({
    url: 'admin/banner/delete/' + id,
    method: 'get',
  });
}
export function batchDeleteBanner(data) {

  return request({
    url: 'admin/banner/batch/delete',
    method: 'post',
    data
  })
}