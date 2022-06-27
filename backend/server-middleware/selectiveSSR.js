export default function(req, res, next) {
  const paths = ['/post/addpost/', '/post/update/1']

  if (new RegExp('/backend*', 'g').test(req.originalUrl)) {
    res.spa = true
  } else if (paths.includes(req.originalUrl)) {
    res.spa = true
  }
  // Don't forget to call next in all cases!
  // Otherwise, your app will be stuck forever :|
  next()
}
