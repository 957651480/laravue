import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'banners',
    method: 'get',
    params: query,
  });
}

export function fetchBanner(id) {
  return request({
    url: 'banners/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: 'banners/' + id + '/pageviews',
    method: 'get',
  });
}

export function createBanner(data) {
  return request({
    url: 'banners',
    method: 'post',
    data,
  });
}

export function updateBanner(id,data) {
  return request({
    url: 'banners/'+id,
    method: 'put',
    data,
  });
}

export function deleteBanner(id) {
  return request({
    url: 'banners/' + id,
    method: 'delete',
  });
}
