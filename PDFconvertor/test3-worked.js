var path = require('path')
var filePath = path.join(__dirname, 'Input.pdf')
var extract = require('pdf-text-extract')
extract(filePath, { splitPages: false }, function (err, text) {
  if (err) {
    console.dir(err)
    return
  }
  console.dir(text)
})
