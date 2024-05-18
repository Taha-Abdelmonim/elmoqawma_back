/**
 * Minified by jsDelivr using Terser v5.19.2.
 * Original file: /npm/bunnycdn-stream@2.2.1/dist/index.umd.js
 *
 * Do NOT use SRI with dynamically generated files! More information: https://www.jsdelivr.com/using-sri-with-dynamic-files
 */
!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e(exports,require("axios"),require("file-type"),require("fs"),require("node:crypto")):"function"==typeof define&&define.amd?define(["exports","axios","file-type","fs","node:crypto"],e):e((t="undefined"!=typeof globalThis?globalThis:t||self).BunnyCdnStream={},t.axios,t.fileType,t.fs,t.crypto)}(this,(function(t,e,i,s,o){"use strict";const a=t=>{const e={};for(const[i,s]of Object.entries(t))e[i[0].toLowerCase()+i.slice(1)]=s;return e},r={404:"NOT_FOUND",400:"BAD_REQUEST",401:"UNAUTHORIZED",403:"FORBIDDEN",500:"INTERNAL_SERVER_ERROR",502:"BAD_GATEWAY",503:"SERVICE_UNAVAILABLE",504:"GATEWAY_TIMEOUT"};class n extends Error{constructor(t,i,s){if(super(),this.name="BunnyCdnStreamError",t instanceof e.AxiosError){if(this.message=`BunnyCdnStreamError: Operation "${i}" - ${t.response?r[t.response.status]:"UNKNOWN_ERROR"} ${t.message}`,this.code=t.response?t.response.status:0,t.response?.data)if("object"==typeof t.response.data){const e=a(t.response.data);"error"in e&&(this.message+=`: ${e.error}`),"message"in e&&(this.message+=`: ${e.message}`)}else this.message+=`: ${JSON.stringify(t.response.data)}`}else this.code=s||-1,this.message=`BunnyCdnStreamError: Unable to ${i}, ${t}`}}class l{constructor(t){this.videoLibraryId=t.videoLibraryId,this.guid=t.guid,this.title=t.title,this.dateUploaded=t.dateUploaded,this.views=t.views,this.isPublic=t.isPublic,this.length=t.length,this.status=t.status,this.framerate=t.framerate,this.width=t.width,this.height=t.height,this.availableResolutions=t.availableResolutions,this.thumbnailCount=t.thumbnailCount,this.encodeProgress=t.encodeProgress,this.storageSize=t.storageSize,this.captions=t.captions,this.hasMP4Fallback=t.hasMP4Fallback,this.collectionId=t.collectionId,this.thumbnailFileName=t.thumbnailFileName,this.moments=t.moments,this.captions=t.captions,this.averageWatchTime=t.averageWatchTime,this.totalWatchTime=t.totalWatchTime,this.category=t.category,this.chapters=t.chapters,this.metaTags=t.metaTags}get resolutions(){return this.availableResolutions.split(",").map((t=>parseInt(t,10)))}}t.BunnyCdnStream=class{constructor(t){this.axiosOptions={headers:new e.AxiosHeaders({Accept:"application/json","Content-Type":"application/json",AccessKey:""}),url:"https://video.bunnycdn.com",method:"GET",maxBodyLength:1/0},this.options=t,this.axiosOptions.headers.AccessKey=this.options.apiKey}async getVideo(t){const e=this.getOptions();e.url+=`/library/${this.options.videoLibrary}/videos/${t}`;const i=await this.request(e,"fetch");return new l(i)}async updateVideo(t,e={}){const i=this.getOptions();return i.url+=`/library/${this.options.videoLibrary}/videos/${t}`,i.method="POST",i.data=JSON.stringify(e),this.request(i,"update")}async deleteVideo(t){const e=this.getOptions();return e.url+=`/library/${this.options.videoLibrary}/videos/${t}`,e.method="DELETE",this.request(e,"delete")}async deleteAllVideos(){const t=async e=>{const{items:i}=await this.listVideos({page:e});0!==i.length&&(await Promise.all(i.map((t=>this.deleteVideo(t.guid)))),await t(e+1))};await t(1)}async createVideo(t){const e=this.getOptions();e.url+=`/library/${this.options.videoLibrary}/videos`,e.method="POST",e.data=JSON.stringify(t);const i=await this.request(e,"create");return new l(i)}async uploadVideo(t,e,i){const s=this.getOptions();return s.url+=`/library/${this.options.videoLibrary}/videos/${e}`,s.method="PUT",s.data=t,s.params=i,s.headers.set("Content-Type","application/octet-stream"),this.request(s,"upload")}async createAndUploadVideo(t,e){const i=await this.createVideo(e);return await this.uploadVideo(t,i.guid),i}async getVideoHeatmap(t){const e=this.getOptions();return e.method="GET",e.url+=`/library/${this.options.videoLibrary}/videos/${t}/heatmap`,this.request(e,"getHeatmap")}async getVideoStatistics(t,e={}){const i=this.getOptions();return i.url+=`/library/${this.options.videoLibrary}/statistics`,i.params={...e,videoGuid:t},this.request(i,"fetch")}async reencodeVideo(t){const e=this.getOptions();e.url+=`/library/${this.options.videoLibrary}/videos/${t}/reencode`,e.method="POST";const i=await this.request(e,"reencode");return new l(i)}async listVideos(t={}){const e=this.getOptions();e.url+=`/library/${this.options.videoLibrary}/videos`,e.params=t;const i=await this.request(e,"list");return{...i,items:i.items.map((t=>new l(t)))}}async listAllVideos(t={},e){const i=[];let s=!0,o=1;for(;s;){const a=await this.listVideos({...t,page:o,itemsPerPage:t.itemsPerPage||100}),r=Math.ceil(a.totalItems/a.itemsPerPage);i.push(...a.items),e&&await e(a.items,o,r)?s=!1:o<r?o++:s=!1}return i}async setThumbnail(t,e,o){const a=this.getOptions(),r=o?{mime:o}:void 0;return"string"!=typeof e&&(a.headers["Content-Type"]=e instanceof s.ReadStream?"application/octet-stream":(r||await i.fromBuffer(e)||{mime:"image/jpg"}).mime),a.url+=`/library/${this.options.videoLibrary}/videos/${t}/thumbnail`,a.method="POST","string"==typeof e?a.params={thumbnailUrl:e}:a.data=e,this.request(a,"setThumbnail")}async fetchVideo(t,e){const i=this.getOptions();return i.url+=`/library/${this.options.videoLibrary}/videos/${t}/fetch`,i.method="POST",i.data=JSON.stringify(e),this.request(i,"fetch")}async addCaptions(t,e){const i=this.getOptions();return i.url+=`/library/${this.options.videoLibrary}/videos/${t}/captions/${e.srclang}`,i.method="POST","string"!=typeof e.captionsFile&&(e.captionsFile=e.captionsFile.toString("base64")),i.data=JSON.stringify(e),this.request(i,"addCaptions")}async deleteCaptions(t,e){const i=this.getOptions();return i.url+=`/library/${this.options.videoLibrary}/videos/${t}/captions/${e}`,i.method="DELETE",this.request(i,"deleteCaptions")}async createCollection(t){const e=this.getOptions();return e.url+=`/library/${this.options.videoLibrary}/collections`,e.method="POST",e.data=JSON.stringify({name:t}),this.request(e,"createCollection")}async getCollection(t){const e=this.getOptions();return e.url+=`/library/${this.options.videoLibrary}/collections/${t}`,this.request(e,"getCollection")}async listCollections(t={}){const e=this.getOptions();e.url+=`/library/${this.options.videoLibrary}/collections`,e.params={...t};return await this.request(e,"listCollections")}async listAllCollections(t={},e){const i=[];let s=!0,o=1;for(;s;){const a=await this.listCollections({...t,page:o,itemsPerPage:t.itemsPerPage||100}),r=Math.ceil(a.totalItems/a.itemsPerPage);i.push(...a.items),e&&await e(a.items,o,r)?s=!1:o<r?o++:s=!1}return i}async updateCollection(t,e){const i=this.getOptions();return i.url+=`/library/${this.options.videoLibrary}/collections/${t}`,i.method="POST",i.data=JSON.stringify(e),this.request(i,"updateCollection")}async deleteCollection(t){const e=this.getOptions();return e.url+=`/library/${this.options.videoLibrary}/collections/${t}`,e.method="DELETE",this.request(e,"deleteCollection")}async deleteAllCollections(){const t=async e=>{const{items:i}=await this.listCollections({page:e});0!==i.length&&(await Promise.all(i.map((t=>this.deleteCollection(t.guid)))),await t(e+1))};await t(1)}async createDirectUpload(t,e){const i=(e||new Date(Date.now()+6e4)).getTime(),s=await this.createVideo(t);return{video:s,endpoint:"https://video.bunnycdn.com/tusupload",headers:{AuthorizationSignature:this.generateTUSHash(s.guid,i),AuthorizationExpire:i,VideoId:s.guid,LibraryId:this.options.videoLibrary},metadata:{filetype:"",title:t.title,collection:t.collection}}}generateTUSHash(t,e){return o.createHash("sha256").update(this.options.videoLibrary.toString()+this.options.apiKey.toString()+e.toString()+t.toString()).digest("hex")}async request(t,i){try{const s=await e.request(t);if("object"==typeof s.data&&(s.data=a(s.data)),"message"in s.data&&"string"==typeof s.data.message&&"statusCode"in s.data&&"number"==typeof s.data.statusCode&&200!==s.data.statusCode)throw new n(s.data.message,i,s.data.statusCode);return s.data}catch(t){throw new n(t,i)}}getOptions(){return{...this.axiosOptions,headers:new e.AxiosHeaders(this.axiosOptions.headers)}}},t.BunnyCdnStreamVideo=l}));
//# sourceMappingURL=/sm/6693ae7366891e88a17a7705010210df704c38b83b480cc2f2fca17b765a2950.map