import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'teachers',
    method: 'get',
    params: query,
  });
}

export function fetchTeacher(id) {
  return request({
    url: 'teachers/' + id,
    method: 'get',
  });
}

export function fetchPv(id) {
  return request({
    url: 'teachers/' + id + '/pageviews',
    method: 'get',
  });
}

export function createFile(data) {
  return request({
    url: 'file/upload',
    method: 'post',
    data,
  });
}

export function updateTeacher(id,data) {
  return request({
    url: 'teachers/'+id,
    method: 'put',
    data,
  });
}

export function deleteTeacher(id) {
  return request({
    url: 'teachers/' + id,
    method: 'delete',
  });
}


export function uploadFile(data) {
  return request({
    url: 'file/upload',
    method: 'post',
    data,
  });
}
