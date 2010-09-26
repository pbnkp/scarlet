require 'nanoc3/tasks'

desc "Copies the compiled site into the gh-pages branch."
task :publish do
  `nanoc co`
  `cp -r output/ /tmp/scarlet-site`
  `git checkout gh-pages`
  `rm -rf *`
  `cp -r /tmp/scarlet-site/* .`
  `rm -rf /tmp/scarlet-site`
end