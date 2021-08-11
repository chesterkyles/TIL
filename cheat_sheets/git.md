# Git

## Git Config (For Aliases)
```
a = add .
rt = restore .
f = fetch
co = checkout
cd = checkout develop
cb = checkout feature/upgrade_bootstrap4_install
br = branch
ci = commit
cm = commit -m
ca = commit --amend
cp = cherry-pick
st = status
sa = stash apply
lone = log --oneline
last = log -1 HEAD
rhone = reset --hard HEAD~1
current = rev-parse --abbrev-ref HEAD
pld = pull origin develop
plm = pull origin main
plb = pull origin feature/upgrade_bootstrap4_install
plc = !CURRENT=$(git current) && git pull origin $CURRENT
psc = !CURRENT=$(git current) && git push origin $CURRENT
new = !git checkout develop && git pull origin develop
brd = !sh -c 'git branch -l $1/* | xargs git branch -D' -
brdall = !sh -c 'git branch -l | xargs git branch -D' -
```

## Git commands
### Get current branch
```sh
git rev-parse --abbrev-ref HEAD
```
### Get all local branches with prefix
```sh
git branch -l <prefix>/*

# Can delete found branches by:
git branch -l <prefix>/* | xargs git branch -D
```

