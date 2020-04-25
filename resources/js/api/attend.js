import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'attends',
    method: 'get',
    params: query,
  });
}

export function fetchAttend(id) {
  return request({
    url: 'attends/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: 'attends/' + id + '/pageviews',
    method: 'get',
  });
}

export function createAttend(data) {
  return request({
    url: 'attends',
    method: 'post',
    data,
  });
}

export function updateAttend(id,data) {
  return request({
    url: 'attends/'+id,
    method: 'put',
    data,
  });
}

export function deleteAttend(id) {
  return request({
    url: 'attends/delete/' + id,
    method: 'get',
  });
}
